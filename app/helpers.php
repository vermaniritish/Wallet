<?php
use App\Models\Admin\Settings;
use Illuminate\Support\Carbon;

function _dt($datetime)
{
	$dateFormat = Settings::get('date_format');
	$timeFormat = Settings::get('time_format');

	return date($dateFormat . ' ' . $timeFormat, strtotime($datetime));
}

function _d($date)
{
	$dateFormat = Settings::get('date_format');
	return date($dateFormat, strtotime($date));
}

function _time($time)
{
	$timeFormat = Settings::get('time_format');
	return date($timeFormat, strtotime($time));
}

function time_ago($time)
{
	$date = new Carbon($time);
    return $date->diffForHumans();
}

function get_controller_action()
{
	$action = request()->route()->getAction()['controller'];
	$action = explode('\\', $action);
	$action = end($action);
	$action = explode('@', $action);
	$controller = str_replace('Controller', '', $action[0]);
	$controller = strtolower(substr($controller, 0, 1)) . substr($controller, 1);
	$action = $action[1];
	return $controller . '/' . $action;
}

function _currency($amount)
{
	return "£" . number_format($amount, 2);
}