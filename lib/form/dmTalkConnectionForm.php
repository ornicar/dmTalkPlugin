<?php

class dmTalkConnectionForm extends dmForm
{
  protected
  $room;

  public function __construct(DmTalkRoom $room)
  {
    $this->room = $room;

    parent::__construct();
  }

  public function getRoom()
  {
    return $this->room;
  }

  public function configure()
  {
    $this->widgetSchema['name']     = new sfWidgetFormInputText();
    $this->validatorSchema['name']  = new dmTalkConnectionValidator($this->room);
  }
}