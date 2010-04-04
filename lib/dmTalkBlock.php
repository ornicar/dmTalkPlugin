<?php

class dmTalkBlock
{
  public
  $speakerName,
  $messages;

  public function __construct($speakerName)
  {
    $this->speakerName  = $speakerName;
    $this->messages     = array();
  }

  public function isBot()
  {
    return 'bot' === $this->speakerName;
  }

  public function addMessage(DmTalkMessage $message)
  {
    $this->messages[] = $message;
  }

  public function getSpeakerName()
  {
    return $this->speakerName;
  }

  public function getMessages()
  {
    return $this->messages;
  }

  public function getCssClass(DmTalkSpeaker $speaker)
  {
    if($this->isBot())
    {
      return 'dm_talk_speaker_bot';
    }
    elseif($this->speakerName === $speaker->get('name'))
    {
      return 'dm_talk_speaker_myself';
    }
    
    return 'dm_talk_speaker_other';
  }
}