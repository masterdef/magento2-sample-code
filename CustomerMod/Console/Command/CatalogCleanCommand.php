class CatalogCleanCommand extends Command
{
    protected function configure()
    {
        $this->setName('catalog:category:clean')
            ->setDescription('Cleans the catalog from extra categories (ending with more than 4 numbers');
        parent::configure();
    }
}

