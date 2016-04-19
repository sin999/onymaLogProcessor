<?php
interface Message{
    const TYPE_UNKNOWN="unknown";
    const TYPE_AUTH_REQUEST="auth_request";
    const TYPE_AUTH_RESPONSE="auth_respose";
    const SOURCE_AUTH="auth";
    const SOURCE_ACC="acc";
    const SOURCE_UNKNOWN="unknown";
    public function toString();
    public function getType();
    public function getDialogueId();
}
?>
