<?php

namespace App;

class Views
{
    public static function render(string $viewFileName, array $data = []): void
    {
        extract($data);
        $cwd = getcwd(); //This will always return the current directory of index.php since it is the entrypoint script
        include $cwd . "/src/views/{$viewFileName}";
        return;
    }

}
