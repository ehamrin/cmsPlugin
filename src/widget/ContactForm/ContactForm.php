<?php


namespace widget\ContactForm;


class ContactForm implements \IWidget
{
    public function DoWidget(...$args)
    {
        $message = isset($_POST['contact_submit']) ? '<p class="success">Thanks!</p>' : '' ;
        return <<<HTML
        <h1>Contact me</h1>
        {$message}
        <form method="POST" action="">
            <input placeholder="Email" type="email"/>
            <input placeholder="Name" type="text"/>
            <textarea placeholder="Message"></textarea>
            <button name="contact_submit">Send</button>
        </form>
HTML;

    }
}