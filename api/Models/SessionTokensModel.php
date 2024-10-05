<?php
/**
 *  $DESCRIPTION$ $END$
 * @name SessionTokensModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models;

class SessionTokensModel extends BaseDatabaseModel implements BaseModelInterface
{

    /**
     * @var array
     */
    protected array $sessionTokens = [];

    /**
     * @param string $userID
     */
    public function __construct(string $userID)
    {
        parent::__construct();

        $stmt = (new \SQLQuery())->select(["id"])->from("sessionTokens")->where()->variableName("userID")->equals()->variable($userID);

        $results = $this->execute($stmt);
        foreach ($results as $result) {
            $this->sessionTokens[] = new SessionTokenModel($result["id"]);
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->sessionTokens;
    }
}