<?

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectricityConnection extends Model
{
    protected $fillable = ['contract_address', 'duration', 'ean_number', 'meter_id'];
}
