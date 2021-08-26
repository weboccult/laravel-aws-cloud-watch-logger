<?php

namespace Weboccult\LaravelAwsCloudWatchLogger\Types;

/**
 * static types
 */
class Operations
{
    public const TEST = 'Test';
    public const ANY = 'Any';
    public const OTHER = 'Other';
    public const GENERAL = 'General';
    public const COMMON = 'Common';
    public const ANONYMOUS = 'Anonymous';

    public const CREATE = 'Create';
    public const UPDATE = 'Update';
    public const DELETE = 'Delete';
    public const EDIT = 'Edit';
    public const VIEW = 'View';
    public const BACKUP = 'Backup';
    public const EXTRACT = 'Extract';
    public const FORCE_DELETE = 'Force-Delete';
    public const GENERAL_MIGRATION = 'General-Migration';
    public const CREATE_ROW = 'Create-Row';
    public const MODIFY_ROW = 'Modify-Row';
    public const DROP_ROW = 'Drop-Row';
    public const CREATE_TABLE = 'Create-Table';
    public const DROP_TABLE = 'Drop-Table';
}
