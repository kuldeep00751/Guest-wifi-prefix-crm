<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ============================
 *        STATES HELPER
 * ============================
 */

/**
 * Get all states
 * @return array
 */
function get_all_states()
{
    $states = get_instance()->db
        ->order_by('sort_order', 'asc')
        ->get(db_prefix() . 'states')
        ->result_array();

    return hooks()->apply_filters('all_states', $states);
}

/**
 * Get state row by ID
 * @param int $id
 * @return object
 */
function get_state($id)
{
    $CI = &get_instance();
    $cache_key = 'db-state-' . $id;

    $state = $CI->app_object_cache->get($cache_key);

    if (!$state) {
        $CI->db->where('id_state', $id);
        $state = $CI->db->get(db_prefix() . 'states')->row();
        $CI->app_object_cache->add($cache_key, $state);
    }

    return hooks()->apply_filters('get_state', $state);
}

/**
 * Get state name by ID
 * @param int $id
 * @return string
 */
function get_state_name($id)
{
    $state = get_state($id);
    return $state ? $state->state : '';
}



/**
 * ============================
 *        CITIES HELPER
 * ============================
 */

/**
 * Get all cities
 * @return array
 */
function get_all_cities()
{
    $cities = get_instance()->db
        ->order_by('sort_order', 'asc')
        ->get(db_prefix() . 'cities')
        ->result_array();

    return hooks()->apply_filters('all_cities', $cities);
}

/**
 * Get city row by ID
 * @param int $id
 * @return object
 */
function get_city($id)
{
    $CI = &get_instance();
    $cache_key = 'db-city-' . $id;

    $city = $CI->app_object_cache->get($cache_key);

    if (!$city) {
        $CI->db->where('id_city', $id);
        $city = $CI->db->get(db_prefix() . 'cities')->row();
        $CI->app_object_cache->add($cache_key, $city);
    }

    return hooks()->apply_filters('get_city', $city);
}

/**
 * Get city name by ID
 * @param int $id
 * @return string
 */
function get_city_name($id)
{
    $city = get_city($id);
    return $city ? $city->city : '';
}
