<?php

namespace app;

use function file_exists;
use function spl_autoload_register;
use function spl_autoload_unregister;
use function str_replace;
use function trim;

/**
 * @property        $namespace
 * @property string $file_location
 */
class Autoload
{

    /**
     * @var System|null
     */
    private System|null $method = null;

    /**
     * @return System|null
     */
    public function load(): ?System
    {
        if ($this->method !== null) {
            return $this->method;
        }
        spl_autoload_register($autoload_function = [
            $this,
            'includes',
        ], false, false);
        $this->method = $method = (new System());
        spl_autoload_unregister($autoload_function);
        $method->register(true);
        return $method;
    }

    /**
     * @param $namespace
     */
    protected function includes($namespace): void
    {
        if (($this->namespace = $namespace) !== null) {
            $this->attach_a_file();
        }
    }

    /**
     *
     */
    private function attach_a_file(): void
    {
        if ($this->check_file_exists()) {
            require_once $this->file_location;
        }
    }

    /**
     * @return bool
     */
    private function check_file_exists(): bool
    {
        $this->file_location = $this->converter_filename();
        return file_exists($this->file_location);
    }

    /**
     * @return string
     */
    private function converter_filename(): string
    {
        return INC_ROOT . '/' . trim(str_replace(
                '\\', '/',
                $this->namespace
            ), '/') . '.php';
    }
}