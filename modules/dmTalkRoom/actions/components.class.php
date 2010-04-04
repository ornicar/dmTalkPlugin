<?php
/**
 * Talk room components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 */
class dmTalkRoomComponents extends myFrontModuleComponents
{

  public function executeApplication(sfWebRequest $request)
  {
    if($this->speaker = $request->getParameter('dm_talk_speaker'))
    {
      $this->room = $this->speaker->Room;
    }
    elseif(isset($this->forms['DmTalkConnectionForm']))
    {
      $this->connectionForm = $this->forms['DmTalkConnectionForm'];
    }
    else
    {
      throw new dmException('Reload the page to enable the talk application');
    }
  }
}