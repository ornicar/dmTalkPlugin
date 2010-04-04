<?php

/**
 * PluginDmTalkMessage form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginDmTalkMessageForm extends BaseDmTalkMessageForm
{
  public function setup()
  {
    parent::setup();

    $this->disableCSRFProtection();

    $this->widgetSchema['text'] = new sfWidgetFormInputText();
  }

  /**
   * @see dmForm::render
   */
  public function render($attributes = array())
  {
    return
    $this->open(dmString::toArray($attributes, true)).
    $this['text']->field().
    $this->close();
  }
}
