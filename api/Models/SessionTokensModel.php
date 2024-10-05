<?php
/**
 *  $DESCRIPTION$ $END$
 * @name SessionTokensModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models;

use OpenPOS\Models\BaseModelInterface;

class SessionTokensModel extends BaseDatabaseModel implements BaseModelInterface
{

    protected array $sessionTokens = [];

    public function __construct(string $userID)
    {
        parent::__construct();

        $results = $this->execute("SELECT id active, timeCreated FROM session_tokens WHERE userID = ?", [$userID]);
        foreach ($results as $result) {
            $this->sessionTokens[] = new SessionTokenModel($result);
        }
    }

    public function toArray(): array
    {
        return $this->sessionTokens;
    }
}