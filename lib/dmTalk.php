<?php

class dmTalk
{
  protected
  $request,
  $user,
  $speaker;

  public function __construct(sfWebRequest $request, sfUser $user)
  {
    $this->request  = $request;
    $this->user     = $user;

    $this->initialize();
  }

  protected function initialize()
  {
    $this->speaker = $this->getOrCreateTalkUser();
  }

  protected function getOrCreateTalkUser()
  {
    if($code = $this->request->getParameter('s'))
    {
      $speaker = dmDb::table('DmTalkSpeaker')->findOneByCode($code);
    }
    else
    {
      $speaker = null;
    }

    if(!$speaker)
    {
      $room = dmDb::table('DmTalkRoom')->create()->saveGet();
      
      $speaker = dmDb::table('DmTalkSpeaker')->createInRoom($room)->saveGet();
    }

    return $speaker;
  }
}