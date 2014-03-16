<?php

function assertValidUpload($code)
{
    if ($code == UPLOAD_ERR_OK) {
        return;
    }

    switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            $msg = 'Image is too large';
            break;

        case UPLOAD_ERR_PARTIAL:
            $msg = 'Image was only partially uploaded';
            break;

        case UPLOAD_ERR_NO_FILE:
            $msg = 'No image was uploaded';
            break;

        case UPLOAD_ERR_NO_TMP_DIR:
            $msg = 'Upload folder not found';
            break;

        case UPLOAD_ERR_CANT_WRITE:
            $msg = 'Unable to write uploaded file';
            break;

        case UPLOAD_ERR_EXTENSION:
            $msg = 'Upload failed due to extension';
            break;

        default:
            $msg = 'Unknown error';
    }

    throw new Exception($msg);
}

?>