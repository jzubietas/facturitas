<?

use App\Exports\Templates\Sheets\Envios\PageclienteDosmeses;

class PlantillaExportClientesdosmesesMultiple implements WithMultipleSheets
{
use Exportable;
protected $anio;

/*public function __construct($anio)
{
$this->anio=$anio;
}
*/
public function sheets(): array
{
    return [
    new PageclienteDosmeses(),
    ];
    }
}
