<?php

echo _tag('form.dm_talk_new_message', array('action' => '+/dmTalkRoom/say?s='.$speaker->code),
  _tag('input name=text type=text')
);