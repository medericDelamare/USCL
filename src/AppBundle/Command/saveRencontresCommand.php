<?php
namespace AppBundle\Command;



use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class saveRencontresCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('save:rencontres')
            ->setDescription('Récupération des données sur le disctrict');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        $parseService = $this->getContainer()->get('parse.parse_scores');
        $parseService->getStats('https://eure.fff.fr/competitions/?id=339167&poule=1&phase=1&type=ch&tab=calendar');

        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }


}