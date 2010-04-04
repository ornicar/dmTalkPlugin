<?php

/**
 * PluginDmTalkSpeakerTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginDmTalkSpeakerTable extends myDoctrineTable
{

  public function findOneByCode($code)
  {
    return $this->createQuery('s')
    ->where('s.code = ?', $code)
    ->leftJoin('s.Room room')
    ->fetchOne();
  }

  public function createBotForRoom(DmTalkRoom $room)
  {
    return $this->create(array(
      'room_id' => $room->id,
      'name'    => 'bot'
    ));
  }
}