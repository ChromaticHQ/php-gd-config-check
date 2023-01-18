<?php

namespace Chromatic\Composer\PhpGdConfigCheck;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Script\Event;

class Plugin implements PluginInterface, EventSubscriberInterface
{
    protected Composer $composer;
    protected IOInterface $io;
    // @TODO: Append support.

    /** @var array<string> */
    protected array $defaultFormats = [
        'FreeType Support',
        'JPEG Support',
        'PNG Support',
        'WebP Support',
    ];

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
        // noop
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
        // noop
    }

    public static function getSubscribedEvents()
    {
        return [
            'post-install-cmd' => 'verifyGDConfig',
        ];
    }

    public function verifyGDConfig(Event $event): void
    {
        $requiredFormats = $this->getRequiredFormats($event);
        $requiredButDisabled = $this->validateGDSupport($requiredFormats);
        if (count($requiredButDisabled) === 0) {
            $event->getIO()->write("🟢 All required PHP GD libraries are enabled.");
            return;
        }
        $errorMessage = '⚠️  The following PHP GD libraries are not enabled in this environment: ';
        foreach ($requiredButDisabled as $item) {
            $errorMessage .= "$item, ";
        }
        $errorMessage = rtrim($errorMessage, ', ');
        $event->getIO()->writeError($errorMessage);
        // @TODO: Figure out the best practice way to force an exit.
    }

    /**
     * Validate GD format support.
     *
     * @param string[] $requiredFormats
     *   An array of required formats.
     *
     * @return string[]
     *   An array of required types that are not enabled..
     */
    protected function validateGDSupport(array $requiredFormats): array
    {

        $gdInfo = gd_info();
        $unsupportedTypes = [];
        foreach ($requiredFormats as $requiredFormat) {
            if (!isset($gdInfo[$requiredFormat]) || $gdInfo[$requiredFormat] === false) {
                $unsupportedTypes[] = $requiredFormat;
            }
        }
        sort($unsupportedTypes);
        return $unsupportedTypes;
    }

    /**
     * Get required GD formats.
     *
     * @param Event $event
     *   The Composer event object
     *
     * @return string[]
     *   An array of required GD formats.
     */
    protected function getRequiredFormats(Event $event): array
    {
        $extra = $event->getComposer()->getPackage()->getExtra();
        $requiredFormats = $extra['php-gd-config-check']['required-formats'] ?? [];
        if (count($requiredFormats) === 0) {
            $requiredFormats = $this->defaultFormats;
        }
        return $requiredFormats;
    }
}
