<?php
class Battery {
    private $manufacturer;
    private $model;
    private $voltage;
    private $ampHours;
    private $weightKg;
    private $dimensionMm;

    public function __construct($manufacturer, $model, $voltage, $ampHours, $weightKg, $wMm, $hMm, $dMm) {
        $this->manufacturer = $manufacturer;
        $this->model = $model;
        $this->voltage = $voltage;
        $this->ampHours = $ampHours;
        $this->weightKg = $weightKg;
        $this->dimensionMm = array($wMm, $hMm, $dMm);
    }

    // 電力量（Wh）の計算
    public function getPowerCapacity() {
        return $this->voltage * $this->ampHours;
    }

    // 同一仕様かどうかの判定（メーカー、モデルは無視）
    public function isEquals(Battery $battery) {
        return $this->voltage == $battery->voltage &&
               $this->ampHours == $battery->ampHours &&
               $this->weightKg == $battery->weightKg &&
               $this->dimensionMm[0] == $battery->dimensionMm[0] &&
               $this->dimensionMm[1] == $battery->dimensionMm[1] &&
               $this->dimensionMm[2] == $battery->dimensionMm[2];
    }

    // このオブジェクトが引数の電池より大きいか（電力量で比較）
    public function isBigger(Battery $battery) {
        return $this->getPowerCapacity() > $battery->getPowerCapacity();
    }

    // isBigger() または isEquals() を再利用して、大きいまたは等しいかの判定
    public function isBiggerOrEqual(Battery $battery) {
        return $this->isBigger($battery) || $this->isEquals($battery);
    }

    // このオブジェクトが引数の電池より小さいかの判定
    public function isSmaller(Battery $battery) {
        // 引数の電池が大きい（または等しくない）場合、小さいと判断
        return !$this->isBiggerOrEqual($battery);
        // または、 return $battery->isBigger($this);
    }

    // 小さいまたは等しいかの判定
    public function isSmallerOrEqual(Battery $battery) {
        return $this->isSmaller($battery) || $this->isEquals($battery);
    }

    // オブジェクトを文字列に変換（電池の仕様とインスタンス参照を表示）
    public function __toString() {
        return "{$this->manufacturer} {$this->model}: {$this->getPowerCapacity()}Wh ({$this->voltage}V/{$this->ampHours}Ah) - " .
               "{$this->dimensionMm[0]}(W)x{$this->dimensionMm[1]}(H)x{$this->dimensionMm[2]}(D) {$this->weightKg}kg";
    }    
}

// サンプル実行（各メソッドの動作確認）
$battery1 = new Battery("メーカーA", "モデルX", 12.0, 100.0, 15.5, 200.0, 150.0, 100.0); // 電力量: 1200Wh
$battery2 = new Battery("メーカーB", "モデルY", 12.0, 80.0, 14.0, 200.0, 150.0, 100.0);  // 電力量: 960Wh
$battery3 = new Battery("メーカーC", "モデルZ", 12.0, 100.0, 15.5, 200.0, 150.0, 100.0); // 電力量: 1200Wh

// 等価性のテスト（仕様が同じ場合）
echo "battery1 is equal to battery2? " . ($battery1->isEquals($battery2) ? "Yes" : "No") . "\n";
echo "battery1 is equal to battery3? " . ($battery1->isEquals($battery3) ? "Yes" : "No") . "\n";

// 大きさの比較（電力量で比較）
echo "battery1 is bigger than battery2? " . ($battery1->isBigger($battery2) ? "Yes" : "No") . "\n";
echo "battery2 is bigger than battery1? " . ($battery2->isBigger($battery1) ? "Yes" : "No") . "\n";

// 大きいまたは等しいかのテスト
echo "battery1 is bigger or equal to battery3? " . ($battery1->isBiggerOrEqual($battery3) ? "Yes" : "No") . "\n";

// 小さいかのテスト
echo "battery2 is smaller than battery1? " . ($battery2->isSmaller($battery1) ? "Yes" : "No") . "\n";

// 小さいまたは等しいかのテスト
echo "battery1 is smaller or equal to battery3? " . ($battery1->isSmallerOrEqual($battery3) ? "Yes" : "No") . "\n";

// __toString() のテスト
echo "\n" . $battery1;
?>
