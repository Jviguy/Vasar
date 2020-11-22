<?php

declare(strict_types=1);

namespace Ghezin\cp\discord;

use Ghezin\cp\discord\Tasks\WebhookTask;
use pocketmine\Server;

class Webhook{

    /** @var string $url the url of the webhook */
	protected $url; 

	public function __construct(string $url){
		$this->url = $url;
	}

    /**
     * @return string a string containing the url
     */
	public function getURL():string{
		return $this->url;
	}

    /**
     * @author CortexPE
     *
     * @return bool represents if the given url is valid
     */
	public function isValid(): bool {
		return filter_var($this->url, FILTER_VALIDATE_URL)!==false;
	}

    /**
     * Asynchronously sends the json content to the discord webhook
     * @author CortexPE
     *
     * @param Message $message to be sent
     */
	public function sendAsync(Message $message) {
		Server::getInstance()->getAsyncPool()->submitTask(new WebhookTask($this, $message));
	}

    /**
     * Synchronously sends the json content to the discord webhook
     * @author Jviguy
     *
     * @param Message $message the message to be sent
     *
     * @return bool Represents the success of the curl operation
     */
	public function send(Message $message): bool {
        $ch = curl_init($this->getURL());
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
        curl_setopt($ch, CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        $return = curl_exec($ch);
        curl_close($ch);
        return $return;
    }
}