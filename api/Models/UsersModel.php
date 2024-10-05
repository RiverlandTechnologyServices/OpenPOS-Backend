<?php
/**
 *  $DESCRIPTION$ $END$
 * @name UsersModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models;

use OpenPOS\Models\BaseDatabaseModel;
use OpenPOS\Models\BaseModelInterface;

class UsersModel extends BaseDatabaseModel implements BaseModelInterface
{
    public function __construct()
    {
        parent::__construct();

        $this->execute("SELECT id FROM users ORDER BY id DESC LIMIT ?", [$max]);
    }

    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }
}