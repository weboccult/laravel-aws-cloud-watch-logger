<?php

namespace Weboccult\LaravelAwsCloudWatchLogger\Types;

/**
 * static types
 */
class Operations
{
    public const ANONYMOUS = 'Anonymous';
    public const ANY = 'Any';
    public const COMMON = 'Common';
    public const GENERAL = 'General';
    public const OTHER = 'Other';
    public const TEST = 'Test';

    public const BACKUP = 'Backup';
    public const CREATE = 'Create';
    public const CREATE_ROW = 'Create-Row';
    public const CREATE_TABLE = 'Create-Table';
    public const DELETE = 'Delete';
    public const DROP_ROW = 'Drop-Row';
    public const DROP_TABLE = 'Drop-Table';
    public const EDIT = 'Edit';
    public const EXTRACT = 'Extract';
    public const FORCE_DELETE = 'Force-Delete';
    public const GENERAL_MIGRATION = 'General-Migration';
    public const MODIFY_ROW = 'Modify-Row';
    public const UPDATE = 'Update';
    public const VIEW = 'View';
}
