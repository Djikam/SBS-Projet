<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        User::create(["name" => "ressources manager", "email" => "Admin@sbs.com", "password" => Hash::make("123456789"), "active" => "1", "type" => "5", "firstconnect" => "ON", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "ADIE A NNOUKA Joseph", "email" => "adie.a.nnouka.joseph@sbs.com", "password" => Hash::make("123456789"), "active" => "1", "type" => "3", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "AGWA SONYIM Alex Romaric", "email" => "agwa.sonyim.alex.romaric@sbs.com", "password" => Hash::make("123456789"), "active" => "0", "type" => "4", "firstconnect" => "ON", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "AKEM Andre Yoan Aurele", "email" => "akem.andre.yoan.aurele@sbs.com", "password" => Hash::make("123456789"), "active" => "1", "type" => "2", "firstconnect" => "ON", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "BELLO Augustin", "email" => "bello.augustin@sbs.com", "password" => Hash::make("123456789"), "active" => "1", "type" => "4", "firstconnect" => "ON", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "BLONDEAU MAKAT Luc", "email" => "blondeau.makat.luc@sbs.com", "password" => Hash::make("123456789"), "active" => "1", "type" => "4", "firstconnect" => "ON", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "CAMARA Mamadou", "email" => "camara.mamadou@sbs.com", "password" => Hash::make("123456789"), "active" => "1", "type" => "3", "firstconnect" => "ON", "conge" => 60, "recuperation" => 60, "agence_id" => 1]);
        User::create(["name" => "CHILIEBOU TCHOUMGOU Pacome Boston", "email" => "chiliebou.tchoumgou.pacome.boston@sbs.com", "password" => Hash::make("123456789"), "active" => "0", "type" => "0", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "DAKEY Narcisse", "email" => "dakey.narcisse@sbs.com", "password" => Hash::make("123456789"), "active" => "0", "type" => "0", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "DEUTCHOUA NOUALEU Michele Valerie", "email" => "deutchoua.noualeu.michele.valerie@sbs.com", "password" => Hash::make("123456789"), "active" => "0", "type" => "0", "firstconnect" => "ON", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "DIOUF Marc", "email" => "diouf.marc@sbs.com", "password" => Hash::make("123456789"), "active" => "1", "type" => "0", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 1]);
        User::create(["name" => "DJIMKOUO NTEMDIEU Kevin", "email" => "djimkouo.ntemdieu.kevin@sbs.com", "password" => Hash::make("123456789"), "active" => "0", "type" => "4", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "FALL Alioune", "email" => "fall.alioune@sbs.com", "password" => Hash::make("123456789"), "active" => "1", "type" => "1", "firstconnect" => "ON", "conge" => 60, "recuperation" => 60, "agence_id" => 1]);
        User::create(["name" => "FOKO Henri Vallant Junior", "email" => "foko.henri.vallant.junior@sbs.com", "password" => Hash::make("123456789"), "active" => "1", "type" => "4", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "KOUA Olivier", "email" => "koua.olivier@sbs.com", "password" => Hash::make("123456789"), "active" => "0", "type" => "4", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 1]);
        User::create(["name" => "KOUAME Alain", "email" => "kouame.alain@sbs.com", "password" => Hash::make("123456789"), "active" => "1", "type" => "1", "firstconnect" => "ON", "conge" => 60, "recuperation" => 60, "agence_id" => 1]);
        User::create(["name" => "LOUADIBE KIANZE Albert", "email" => "louadibe.kianze.albert@sbs.com", "password" => Hash::make("123456789"), "active" => "0", "type" => "4", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "MBASSA Gavis Donald", "email" => "mbassa.gavis.donald@sbs.com", "password" => Hash::make("123456789"), "active" => "1", "type" => "0", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 1]);
        User::create(["name" => "MBODJ Maty", "email" => "mbodj.maty@sbs.com", "password" => Hash::make("123456789"), "active" => "0", "type" => "0", "firstconnect" => "ON", "conge" => 60, "recuperation" => 60, "agence_id" => 1]);
        User::create(["name" => "MBOULA Hermann", "email" => "mboula.hermann@sbs.com", "password" => Hash::make("123456789"), "active" => "1", "type" => "0", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "N'DA Assemien Patrice", "email" => "n'da.assemien.patrice@sbs.com", "password" => Hash::make("123456789"), "active" => "0", "type" => "0", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 1]);
        User::create(["name" => "NANA TCHANKO Roussel", "email" => "nana.tchanko.roussel@sbs.com", "password" => Hash::make("123456789"), "active" => "0", "type" => "1", "firstconnect" => "ON", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "NDJOM MBENGUE Francis", "email" => "ndjom.mbengue.francis@sbs.com", "password" => Hash::make("123456789"), "active" => "0", "type" => "3", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "NDZENGUE NYEME Marie-Florence", "email" => "ndzengue.nyeme.marie-florence@sbs.com", "password" => Hash::make("123456789"), "active" => "1", "type" => "3", "firstconnect" => "ON", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "NGANSOP NJIKE Bolivan Wilfried", "email" => "ngansop.njike.bolivan.wilfried@sbs.com", "password" => Hash::make("123456789"), "active" => "1", "type" => "1", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 1]);
        User::create(["name" => "NGANTCHANG DITCHOU Christian", "email" => "ngantchang.ditchou.christian@sbs.com", "password" => Hash::make("123456789"), "active" => "1", "type" => "2", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "NGOMSEU TCHAGNA Fabrice", "email" => "ngomseu.tchagna.fabrice@sbs.com", "password" => Hash::make("123456789"), "active" => "0", "type" => "1", "firstconnect" => "ON", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "NGUEMFO WAMBA Arlette", "email" => "nguemfo.wamba.arlette@sbs.com", "password" => Hash::make("123456789"), "active" => "1", "type" => "3", "firstconnect" => "ON", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "OURA Elsa", "email" => "oura.elsa@sbs.com", "password" => Hash::make("123456789"), "active" => "1", "type" => "2", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 1]);
        User::create(["name" => "POKEM KENLACK Denis", "email" => "pokem.kenlack.denis@sbs.com", "password" => Hash::make("123456789"), "active" => "0", "type" => "0", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "SIEWE MONTHEU Pierre Freddy", "email" => "siewe.montheu.pierre.freddy@sbs.com", "password" => Hash::make("123456789"), "active" => "1", "type" => "3", "firstconnect" => "ON", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "SILATCHA Brice Armel", "email" => "silatcha.brice.armel@sbs.com", "password" => Hash::make("123456789"), "active" => "0", "type" => "3", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "TENDJANG NOUKOUA Anthony", "email" => "tendjang.noukoua.anthony@sbs.com", "password" => Hash::make("123456789"), "active" => "1", "type" => "3", "firstconnect" => "ON", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "TIMMA FANKEM Rodrigue Gildas", "email" => "timma.fankem.rodrigue.gildas@sbs.com", "password" => Hash::make("123456789"), "active" => "0", "type" => "1", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "TSAJIO DONFACK Berlain", "email" => "tsajio.donfack.berlain@sbs.com", "password" => Hash::make("123456789"), "active" => "0", "type" => "2", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "YOMBA Ronald", "email" => "yomba.ronald@sbs.com", "password" => Hash::make("123456789"), "active" => "1", "type" => "3", "firstconnect" => "ON", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
        User::create(["name" => "BONGO Boni Germain", "email" => "bongo.boni.germain@sbs.com", "password" => Hash::make("123456789"), "active" => "0", "type" => "3", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 1]);
        User::create(["name" => "DIOUF Mouhamadou", "email" => "diouf.mouhamadou@sbs.com", "password" => Hash::make("123456789"), "active" => "0", "type" => "3", "firstconnect" => "ON", "conge" => 60, "recuperation" => 60, "agence_id" => 1]);
        User::create(["name" => "SOUARE Sekou", "email" => "souare.sekou@sbs.com", "password" => Hash::make("123456789"), "active" => "0", "type" => "3", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 1]);
        User::create(["name" => "MANFOUO KENNE Blaise", "email" => "manfouo.kenne.blaise@sbs.com", "password" => Hash::make("123456789"), "active" => "0", "type" => "1", "firstconnect" => "NO", "conge" => 60, "recuperation" => 60, "agence_id" => 2]);
    }
}

