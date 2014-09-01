<?php namespace LW\SocketClient;

class Client {

	/**
	 * @var Resource
	 */
	protected $client;

	/**
	 * @var string
	 */
	protected $scheme;

	/**
	 * @var string
	 */
	protected $hostname;

	/**
	 * @param int
	 */
	protected $port;

	/**
	 * @param string $hostname
	 * @param int $port
	 * @param string $scheme
	 */
	public function __construct($hostname = null, $port = null, $scheme = 'tcp')
	{
		$this->scheme = $scheme;

		if (! is_null($hostname))
		{
			$this->hostname = $hostname;
		}

		if (! is_null($port))
		{
			$this->port = $port;
		}
	}

	/**
	 * @param string $value
	 * @return static
	 */
	public function setHostname()
	{
		$this->hostname = $value;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getHostname()
	{
		return $this->hostname;
	}

	/**
	 * @param int $value
	 * @return static
	 */
	public function setPort($value)
	{
		$this->port = intval($value);

		return $this;
	}

	/**
	 * @return int
	 */
	public function getPort()
	{
		return $this->port;
	}

	/**
	 * @param string $value
	 * @return static
	 */
	public function setScheme()
	{
		$this->sheme = $value;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getScheme()
	{
		return $this->scheme;
	}

	/**
	 * @return bool
	 */
	public function connected()
	{
		return !! $this->client;
	}

	/**
	 * @return static
	 * @throws \RuntimeException
	 */
	public function connect()
	{
		$this->client = stream_socket_client($this->stringify(), $err, $errmsg, 30);

		if ($err)
		{
			throw new \RuntimeException('Legalwebb Socket Client: ' . $errmsg);
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function stringify()
	{
		return $scheme . '://' . $this->hostname . ':' . intval($this->port);
	}

	/**
	 * @param int $length
	 * @param int $offset
	 * @return string
	 */
	public function read($length = -1, $offset = -1)
	{
		return stream_get_contents($this->client, $offset, $length);
	}

	/**
	 * @param string $content
	 * @param int $length
	 */
	public function write($content, $length = null)
	{
		if (is_null($length))
		{
			$length = strlen($content);
		}

		return fwrite($this->client, $content, $length);
	}

	/**
	 * @return void
	 */
	public function close()
	{
		fclose($this->client);
		$this->client = null;
	}

	/**
	 * @see \LW\SocketClient\Client::close()
	 */
	public function __destruct()
	{
		if (is_resource($this->client))
		{
			$this->close();
		}
	}

}