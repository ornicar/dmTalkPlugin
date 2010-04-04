<?php

class dmTalkConnectionValidator extends sfValidatorString
{
  protected
  $room;

  public function __construct(DmTalkRoom $room)
  {
    $this->room = $room;

    parent::__construct();
  }

  public function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addMessage('not_acceptable', '"%value%" is not an acceptable nickname.');
    $this->addMessage('exists', 'The nickname "%value%" is is already in use.');

    $this->setOption('max_length', 32);
    $this->setOption('min_length', 2);
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $value = parent::doClean($value);

    $value = trim($value);

    if(empty($value))
    {
      throw new sfValidatorError($this, 'not_acceptable', array('value' => $value));
    }
    
    if($this->room->hasSpeakerByName($value))
    {
      throw new sfValidatorError($this, 'exists', array('value' => $value));
    }

    return $value;
  }
}