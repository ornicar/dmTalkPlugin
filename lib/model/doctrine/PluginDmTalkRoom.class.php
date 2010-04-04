<?php

abstract class PluginDmTalkRoom extends BaseDmTalkRoom
{
  
  public function getBlocksForSpeaker(DmTalkSpeaker $speaker)
  {
    $blocks     = array();
    $speakerName  = null;

    foreach($this->getMessagesForSpeaker($speaker) as $message)
    {
      if('bot' === $message->get('speaker_name') || $speakerName !== $message->get('speaker_name'))
      {
        $speakerName  = $message->get('speaker_name');
        $blocks[]     = $block = new dmTalkBlock($speakerName);
      }

      $block->addMessage($message);
    }

    return $blocks;
  }

  public function getMessagesForSpeaker(DmTalkSpeaker $speaker)
  {
    $messages   = $this->get('Messages');
    $speakerId  = $speaker->get('id');

    foreach($messages as $index => $message)
    {
      $toSpeakerId = $message->get('to_speaker_id');
      if($toSpeakerId && $toSpeakerId != $speakerId)
      {
        unset($messages[$index]);
      }
    }

    return $messages;
  }

  public function getHumanSpeakers()
  {
    $humanSpeakers = array();

    foreach($this->get('Speakers') as $speaker)
    {
      if(!$speaker->isBot())
      {
        $humanSpeakers[] = $speaker;
      }
    }

    return $humanSpeakers;
  }

  public function getBotSpeaker()
  {
    foreach($this->get('Speakers') as $speaker)
    {
      if($speaker->isBot())
      {
        return $speaker;
      }
    }

    throw new dmRecordException('This room has no bot');
  }

  public function getHash()
  {
    return md5(serialize(array($this->getSpeakerNames(), $this->getNbMessages())));
  }

  public function getNbMessages()
  {
    return dmDb::query('DmTalkMessage m')
    ->where('m.room_id = ?', $this->id)
    ->count();
  }

  public function getSpeakerNames()
  {
    $names = array();

    foreach($this->Speakers as $speaker)
    {
      $names[] = $speaker->get('name');
    }

    return $names;
  }

  public function hasSpeakerByName($name)
  {
    return in_array($name, $this->getSpeakerNames());
  }

  public function createSpeaker($name)
  {
    return dmDb::table('DmTalkSpeaker')->create(array(
      'name'    => $name,
      'room_id' => $this->id
    ));
  }

  public function preInsert($event)
  {
    $this->code = dmString::random(8);

    return parent::preInsert($event);
  }
  
  public function postInsert($event)
  {
    dmDb::table('DmTalkSpeaker')->createBotForRoom($this)->save();

    return parent::preInsert($event);
  }
}