<?php


namespace Form;


class Settings
{
    /*
     * Disable if you have you own autoloader
     * the can load all the appropriate classes
     *
     * NOT RECOMMENDED
     */
    public static $UseFormAutoLoader = FALSE;

    /*
     * Allow the library to redirect it's users
     * in a post-redirect-get pattern. You also need to have a session
     * running to enable the PRG
     */
    public static $UsePRG = FALSE;

    /*
     * If false, the checkbox index will not be populated when exported from the FormHandler if it is not checked, if it is, it will export "on"
     * If true, the index will always be populated with true or false
     */
    public static $PopulateCheckboxIndex = TRUE;


    /*
     * False value removes the submit button from the
     * exported data array, if you want it exported change the value
     * to TRUE
     */
    public static $PopulateSubmitIndex = FALSE;

    /*
     * Directory to input template files,
     * if the template file is not found it will revert to
     * the default view for the input.
     *
     * You can also add the base template to this directory and it will be loaded instead of the one
     * that comes with the library
     */
    public static $TemplateDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'view/Templates/';
}