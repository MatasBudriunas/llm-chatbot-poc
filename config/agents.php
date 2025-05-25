<?php

declare(strict_types=1);

return [
    'generation_prompt' => <<<EOT
You are a helpful AI assistant.

Your task is to generate a clear and concise *personality profile* for a character based on the description provided by the user.

ONLY return the personality text. Do NOT include any extra commentary, headers, labels, or formatting.

The personality should reflect the character’s tone, behavior, and speaking style. Here’s the description:

===
%s
===
EOT,
];
