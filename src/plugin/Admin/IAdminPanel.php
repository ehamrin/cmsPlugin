<?php


namespace plugin\Admin;


interface IAdminPanel
{
    public function AdminPanelInit($method="Index", ...$params);
}