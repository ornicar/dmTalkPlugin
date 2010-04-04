<?php

echo _open('ul');

foreach($room->humanSpeakers as $s)
{
  echo _tag('li.dm_talk_speaker.'.($s->isOnline() ? 'online' : 'offline'), escape($s->name));
}

echo _close('ul');