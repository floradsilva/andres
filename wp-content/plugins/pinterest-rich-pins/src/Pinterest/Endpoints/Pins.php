<?php
/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vsourz\Pinterest\Endpoints;

use Vsourz\Pinterest\Models\Pin;
use Vsourz\Pinterest\Models\Collection;

class Pins extends Endpoint {

    /**
     * Get a pin object
     *
     * @access public
     * @param  string   $pin_id
     * @param array     $data
     * @throws \Vsourz\Pinterest\Exceptions\PinterestException
     * @return Pin
     */
    public function get($pin_id, array $data = [])
    {
        $response = $this->request->get(sprintf("pins/%s", $pin_id), $data);
        return new Pin($this->master, $response);
    }

    /**
     * Get all pins from the given board
     *
     * @access public
     * @param  string   $board_id
     * @param array     $data
     * @throws \Vsourz\Pinterest\Exceptions\PinterestException
     * @return Collection
     */
    public function fromBoard($board_id, array $data = [])
    {
        $response = $this->request->get(sprintf("boards/%s/pins", $board_id), $data);
        if(!empty($response) && $response['code'] >= 400){
            return $response;
        }
        $new_response = new Collection($this->master, $response['data'], "Pin");
        $response_data = array(
                                'success' => 'true' ,
                                'code' => '200',
                                'message' => '',
                                'data' => $new_response
                            );
        return $response_data;

    }

    /**
     * Create a pin
     *
     * @access public
     * @param  array    $data
     * @throws \Vsourz\Pinterest\Exceptions\PinterestException
     * @return Pin
     */
    public function create(array $data)
    {
        if (array_key_exists("image", $data)) {
            if (class_exists('\CURLFile')) {
                $data["image"] = new \CURLFile($data['image']);
            } else {
                $data["image"] = '@' . $data['image'];
            }
        }

        $response = $this->request->post("pins", $data);

        if(!empty($response) && $response['code'] >= 400){
            return $response;
        }

        $new_response = new Pin($this->master, $response['data']);

        $response_data = array(
                                'success' => 'true' ,
                                'code' => '200',
                                'message' => '',
                                'data' => $new_response
                            );

        return $response_data;

    }

    /**
     * Edit a pin
     *
     * @access public
     * @param  string   $pin_id
     * @param  array    $data
     * @param  string   $fields
     * @throws \Vsourz\Pinterest\Exceptions\PinterestException
     * @return Pin
     */
    public function edit($pin_id, array $data, $fields = null){
		
        $query = (!$fields) ? array() : array("fields" => $fields);

        $response = $this->request->update(sprintf("pins/%s/", $pin_id), $data, $query);
        return new Pin($this->master, $response['data']);
    }

    /**
     * Delete a pin
     *
     * @access public
     * @param  string   $pin_id
     * @throws \Vsourz\Pinterest\Exceptions\PinterestException
     * @return boolean
     */
    public function delete($pin_id)
    {
        $this->request->delete(sprintf("pins/%s", $pin_id));
        return true;
    }
}