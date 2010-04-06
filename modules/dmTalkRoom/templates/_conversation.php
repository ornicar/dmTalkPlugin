<?php
use_helper('Text');

echo _open('ol.dm_talk_blocks');

foreach($room->getBlocksForSpeaker($speaker) as $block)
{
  echo _open('li.dm_talk_block.'.$block->getCssClass($speaker));

    if(!$block->isBot())
    {
      echo _tag('span.speaker', escape($block->getSpeakerName()));
    }

    echo _open('ol.dm_talk_messages');

    foreach($block->getMessages() as $message)
    {
      echo _tag('li.dm_talk_message',
        _tag('span.date', date('H:i', strtotime($message->get('created_at')))).
        _tag('span.text',
          ($message->ToSpeaker ? _tag('span.to', $message->ToSpeaker->name) : '').
          auto_link_text(nl2br(escape($message->get('text'))), 'urls')
        )
      );
    }

    echo _close('ol');

  echo _close('li');
}

echo _close('ol');