namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model {
    protected $fillable = ['sale_id', 'due_date', 'amount'];

    public function sale() {
        return $this->belongsTo(Sale::class);
    }
}
