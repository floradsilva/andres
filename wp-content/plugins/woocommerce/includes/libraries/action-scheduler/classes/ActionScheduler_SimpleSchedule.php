<?php

/**
 * Class ActionScheduler_SimpleSchedule
 */
class ActionScheduler_SimpleSchedule implements ActionScheduler_Schedule
{
    private $date = null;
    private $timestamp = 0;
    public function __construct(DateTime $date)
    {
        $this->date = clone $date;
    }

    /**
     * @param DateTime $after
     *
     * @return DateTime|null
     */
    public function next(DateTime $after = null)
    {
        $after = empty($after) ? as_get_datetime_object('@0') : $after;
        return ($after > $this->date) ? null : clone $this->date;
    }

    /**
     * @return bool
     */
    public function is_recurring()
    {
        return false;
    }

    /**
     * For PHP 5.2 compat, since DateTime objects can't be serialized
     * @return array
     */
    public function __sleep()
    {
        $this->timestamp = $this->date->getTimestamp();
        return array(
            'timestamp',
        );
    }

    public function __wakeup()
    {
        $this->date = as_get_datetime_object($this->timestamp);
    }
}
