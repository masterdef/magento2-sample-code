<?php

namespace Litslink\Track24\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use \Magento\Sales\Model\ResourceModel\Order\CollectionFactory as CollectionFactory;
use \Magento\Framework\App\State as AppState;
use \Magento\Sales\Model\Order as Order;
use \Litslink\Track24\Model\Track24 as Track24;
use \Litslink\Track24\Model\Sender\UpdateSender as Sender;

class TrackingStatusUpdateCommand extends Command
{
    /**
     * @var $collectionFactory
     */
    private $collectionFactory;
    private $_track24;

    private $appState;

    /**
     * {@inheritdoc}
     *
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        Track24 $track24,
        Sender $sender,
        AppState $appState,
        $name = null
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->_track24 = $track24;
        $this->appState = $appState;
        $this->_sender = $sender;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('track24:status:update')
            ->setDescription('Fetch track24 shipment status updates')
            ;

        parent::configure();
    }

    /**
     * To run: php bin/magento track24:status:update
     **/
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->appState->setAreaCode('us');
        $sales = $this->collectionFactory->create();
        $sales->addFieldToFilter('status', Order::STATE_PROCESSING);

        foreach ($sales as $order)
        {
          foreach ($order->getShipmentsCollection() as $shipments)
          {
            foreach ($shipments->getTracksCollection() as $track)
            {
              $track_number = $track->getData('track_number');
              $data = $this->_track24->getTrackingData($track_number);

              // Test if shipment status changed
              if (md5($data) != md5($order->getData('shipment_tracking_status')))
              {
                $order->setData('shipment_tracking_status', $data);
                $order->save();
                $output->writeln('new_status_saved:' . $track_number);

                // Send email notification to customer
                $this->_sender->send($order);
              }
            }
          }
        }
    }
}

