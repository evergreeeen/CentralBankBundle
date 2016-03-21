<?php
/**
 * Created by PhpStorm.
 * User: ZhukovAD
 * Date: 21.03.2016
 * Time: 17:11
 */

namespace AJStudio\CentralBankBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;

abstract class GeneralCommand extends ContainerAwareCommand
{
    /**
     * @param OutputInterface $output
     * @param string $text
     */
    protected function writeComment(OutputInterface $output, $text) {
        $output->writeln('<comment>' . $text . '</comment>');
    }

    /**
     * @param OutputInterface $output
     * @param string $text
     */
    protected function writeError(OutputInterface $output, $text) {
        $output->writeln('<error>' . $text . '</error>');
    }
}