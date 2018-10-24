<?php

$commands = collect([
	'help' => 'list of commands',
	'stop' => 'stops the conversation',
	'join' => 'auto-registration',
	'info' => 'information about the campaign',

	'share' 	=> 'upload multimedia',
	'subscribe' => 'turn on/off news, bulletins and blogs',
	'survey' 	=> 'access specific intel questions',
	'update' 	=> 'updat personal attributes',
	'volunteer' => 'volunteer as pollwatcher',
	'watch' 	=> 'start poll-watching tasks',
	'send' 		=> 'person-to-person messaging via handle',
	'broadcast' => 'group messaging via group name',
	'stregth' 	=> 'current membership count by groups',
	'votes' 	=> 'quick-count by clustered precincts, polling centers, barangays, districts and LGUs',
]);

$keywords = $commands->map(function ($description, $command) { 
	return $command . ' - ' . $description;
})->implode("\n");

return [
    'help' => env('BOT_RESPONSE_HELP', $keywords),
    'stop' => env('BOT_RESPONSE_STOP', 'Break, break, break...'),
    'info' => env('BOT_RESPONSE_INFO', 'The quick brown fox...'),
];
