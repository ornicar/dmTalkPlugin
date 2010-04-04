<?php

abstract class PluginDmTalkSpeaker extends BaseDmTalkSpeaker
{

  public function isBot()
  {
    return 'bot' === $this->get('name');
  }

  public function say($message, DmTalkSpeaker $toSpeaker = null)
  {
    dmDb::table('DmTalkMessage')->createForSpeaker($this, $message, $toSpeaker)->save();
  }

  public function rename($name)
  {
    $oldName = $this->name;
    $this->name = $name;
    $this->save();
    $this->getEventDispatcher()->notify(new sfEvent($this, 'dm_talk.speaker.renamed', array(
      'old_name' => $oldName,
      'new_name' => $name
    )));
  }

  public function ping()
  {
    $this->last_ping = $_SERVER['REQUEST_TIME'];
    $this->save();
  }

  public function isOnline()
  {
    return $this->last_ping > ($_SERVER['REQUEST_TIME'] - (2 + 2 * (sfConfig::get('dm_talk_ajax_refresh_delay', 2000)/1000)));
  }

  public function preInsert($event)
  {
    $this->code = dmString::random(8);

    $this->last_ping = $_SERVER['REQUEST_TIME'];

    return parent::preInsert($event);
  }

  public function postInsert($event)
  {
    $this->getEventDispatcher()->notify(new sfEvent($this, 'dm_talk.speaker.created'));

    return parent::postInsert($event);
  }
}