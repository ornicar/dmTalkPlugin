<?php

class dmTalkPluginConfiguration extends sfPluginConfiguration
{
  protected
  $context,
  $i18n;
  
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    $this->dispatcher->connect('dm.context.loaded', array($this, 'listenToContextLoadedEvent'));
  }
  
  public function listenToContextLoadedEvent(sfEvent $e)
  {
    $eventLog = $e->getSubject()->get('event_log');
    
    $eventLog->setOption('ignore_models', array_merge($eventLog->getOption('ignore_models'), array(
      'DmTalkSpeaker',
      'DmTalkMessage'
    )));

    $this->context  = $e->getSubject();
    $this->i18n     = $this->context->getI18n();

    $this->dispatcher->connect('dm_talk.speaker.created', array($this, 'listenToSpeakerCreatedEvent'));

    $this->dispatcher->connect('dm_talk.speaker.renamed', array($this, 'listenToSpeakerRenamedEvent'));
  }

  public function listenToSpeakerCreatedEvent(sfEvent $event)
  {
    $speaker = $event->getSubject();

    if($speaker->isBot())
    {
      if(class_exists('dmFrontLinkTagPage'))
      {
        $speaker->say(str_replace('http://', "\nhttp://", $this->i18n->__("Give this url to invite someone to join the room: %url%", array(
          '%url%' => $this->context->getHelper()->link($this->context->getPage())->param('r', $speaker->Room->code)->getAbsoluteHref())
        )));
      }
    }
    else
    {
      $speaker->Room->BotSpeaker->say($this->i18n->__('%name% has joined the room', array(
        '%name%' => $speaker->get('name')
      )));
    }
  }

  public function listenToSpeakerRenamedEvent(sfEvent $event)
  {
    $event->getSubject()->Room->BotSpeaker->say($this->i18n->__('%old_name% changed name to %new_name%', array(
      '%old_name%' => $event['old_name'],
      '%new_name%' => $event['new_name']
    )));
  }

}