<?php
namespace Platform\Models\Core;

use Illuminate\Database\Eloquent\Model;

use App\Scopes\AccountScope;

class TaxRate extends Model
{

  protected $table = 'tax_rates';

  public static function boot() {
    parent::boot();

    static::addGlobalScope(new AccountScope(auth()->user()));

    // On create
    self::creating(function ($model) {
      if (auth()->check()) {
        $model->account_id = auth()->user()->account_id;
      }
    });
  }

  /**
   * Get default unit name.
   *
   * @return number
   */
  static public function getDefault() {
    return (TaxRate::where('default', 1)->first() !== null) ? TaxRate::where('default', 1)->first()->rate : '';
  }

  /**
   * Get tax rate percentage.
   *
   * @return string
   */
  public function getPercentageAttribute() {
    return str_replace(auth()->user()->getDecimalSep() . '00', '', number_format($this->rate / 100, auth()->user()->getDecimals(), auth()->user()->getDecimalSep(), auth()->user()->getThousandsSep())) . '%';
  }

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;
}