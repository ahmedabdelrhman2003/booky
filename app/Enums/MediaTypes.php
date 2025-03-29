<?php

namespace App\Enums;

enum MediaTypes : string
{
    case AUTHOR_PICTURE = 'author-image';
    case BOOK_COVER = 'book-cover';
    case BOOK_PDF = 'book';
    case BOOK_AUDIO = 'audio';


}
