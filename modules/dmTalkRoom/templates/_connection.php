<?php

echo _tag('div.dm_talk', _tag('div.dm_talk_connection_form',
  _tag('p.dm_talk_connection_message', __('Please enter your nickname')).
  $form->open().
  $form['name']->error()->field().
  $form->submit(__('Enter')).
  $form->renderHiddenFields().
  $form->close()
));