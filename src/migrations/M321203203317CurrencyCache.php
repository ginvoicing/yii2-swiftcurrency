<?php
/**
 * Created by PhpStorm.
 * User: tarunjangra
 * Date: 01/02/2021
 * Time: 08:15
 */

namespace yii\swiftcurrency\migrations;

use \yii\swiftcurrency\caching\Cache;
use yii\swiftcurrency\exceptions\BadCachingProvider;

class M321203203317CurrencyCache extends \yii\db\Migration
{
    public $tableName = '{{%swiftcurrency_cache}}';

    public function init()
    {
        /**
         * @var Cache $cacheProvider
         */
        $cacheProvider = \Yii::$app->swiftcurrency->getCachingProvider();
        if ($cacheProvider === false) {
            throw new BadCachingProvider('Caching agent is required.');
        }
        $this->tableName = $cacheProvider->getTableName();
        $this->db = $cacheProvider->getConnection();
    }

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'timeline' => $this->timestamp()->defaultExpression('NOW()'),
            'base_currency' => $this->string(3)->notNull(),
            'provider' => $this->string(100),
            'all' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'usd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'afn' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'azm' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'ars' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'awg' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'ats' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'aud' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'azn' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'bsd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'bef' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'bbd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'byr' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'eur' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'bzd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'bmd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'bob' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'bam' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'btn' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'bwp' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'byn' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'bgn' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'brl' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'gbp' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'cnh' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'cuc' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'cyp' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'dem' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'bnd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'khr' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'cad' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'cdf' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'kyd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'eek' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'esp' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'fim' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'frf' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'clp' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'grd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'htg' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'cny' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'cop' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'crc' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'hrk' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'iep' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'inr' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'itl' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'lrd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'lsl' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'luf' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'mgf' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'mro' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'cup' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'czk' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'dkk' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'xcd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'egp' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'svc' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'fkp' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'fjd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'ghc' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'gip' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'gtq' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'ggp' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'gyd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'hnl' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'hkd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'huf' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'isk' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'skk' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'irn' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'idr' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'irr' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'imp' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'ils' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'jmd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'jpy' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'jep' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'kzt' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'kpw' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'krw' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'kgs' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'lak' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'lvl' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'lbp' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'ldr' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'chf' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'ltl' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'mkd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'myr' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'mur' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'mxn' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'mnt' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'mzn' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'nad' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'npr' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'ang' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'nzd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'nio' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'ngn' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'nok' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'nlg' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'omr' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'pkr' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'pkg' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'php' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'pte' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'pab' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'pln' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'pyg' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'pen' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'qar' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'ron' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'rub' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'rol' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'shp' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'sar' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'rsd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'sdd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'scr' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'sgd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'sbd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'sos' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'zar' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'lkr' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'sek' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'srd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'syp' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'twd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'thb' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'ttd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'try' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'trl' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'tvd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'uah' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'uyu' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'uzs' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'vef' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'vnd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'yer' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'zwd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'dzd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'aoa' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'amd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'bhd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'bdt' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'xof' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'bif' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'xaf' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'xag' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'xau' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'xbt' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'xdr' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'cve' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'kmf' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'djf' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'dop' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'ern' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'etb' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'xpf' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'xpd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'xpt' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'gmd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'gel' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'ghs' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'gnf' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'iqd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'jod' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'kes' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'kwd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'lyd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'mop' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'mga' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'mwk' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'mvr' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'mru' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'mdl' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'mad' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'mmk' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'pgk' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'rwf' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'wst' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'std' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'sll' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'sit' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'ssp' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'spl' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'stn' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'sdg' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'szl' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'tjs' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'tzs' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'top' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'tnd' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'tmt' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'tmm' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'mtl' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'mxv' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'val' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'veb' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'ved' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'ves' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'mzm' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'ugx' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'aed' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'vuv' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'zmw' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'zmk' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'zwl' => $this->decimal(25, 12)->notNull()->defaultValue(0),
            'raw' => $this->json(),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE NOW()'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
