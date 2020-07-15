<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Nesk\Puphpeteer\Puppeteer;



class ExposureController extends Controller
{

    public function screenShot(Request $request)
    {
        $graphicCreatorUrl = env("FRONTEND_URL");
        $url = "{$graphicCreatorUrl}?event={$request->input('text')}&display={$request->input('display')}";
        $url = "http://localhost:3000?event=%22Prep%20Hoops%20_HASHTAGHardWorkSzn%20Kick%20Off%22,%C4%81%C4%88l%201%C4%A9%C4%ABo%C4%AD2%C4%B0P%C4%AC%203%C4%B5%C4%B74%C4%BA%C4%B2%205%C4%BD%C4%AD6%C5%81%207%C5%848%C5%849%C5%8410%C4%B0MN%20Select%20%C4%A5hnstad%C4%B0WI%20Playground%20UAA%C4%B0IL%C4%86%C4%88pS%C5%9Dr%C4%8BNationa%C4%AD%C4%B0H.I.T%C5%98Elite%C5%A0%C5%A2Crus%C5%9Ee%C5%BA%20AD%20%C6%90%20I%C5%AC%C4%84e%C6%A0n%C5%97%C5%B2%C5%90Re%C5%AD%C5%B8%C4%9Am%C6%9C%C4%AATea%C6%ADM.O.R.E.%C6%99th%C5%94%C5%BEc%C4%8B%C5%B2%C5%B4T%C4%99lve%C7%82%C4%AAABC%20Y%C5%AAng%20L%C5%BF%C5%9B%20B%C5%A5%C4%A3%C6%AE%22W%C5%BD%C6%97l%C4%88%C5%91%C6%82v%C5%BD%C7%96%C7%9C%C5%A1%C7%98%C6%8Czz%C4%96%C5%ADL%C5%93a%C5%AC%C7%A8%C5%A2%C6%9Fd%C6%A1%C6%A3%C6%A5%C4%AA%C5%8F%C5%91%C5%93%C5%95%C5%97%20Robi%C5%9B%C6%80%C7%83%C5%B5%C4%89%C5%B8%C4%96%C4%8B%C6%8B%C6%8De%20-%20N%C5%93s%C6%80%C7%9C%C7%8C%C7%8E%C7%90%C5%AB%C7%93%C7%95%C6%80%C4%8BWh%C8%8F%C7%9C%C7%BC%C6%92ossfi%C4%83%C8%91%C5%91t%C6%80%C6%8F%C6%AF%C6%B1%C6%AD1848%C7%9C%C5%B3%C8%89%C4%8A%C5%B9%C4%8BC%C6%A2tr%C6%82%C4%B0Fox%20V%C6%82%C5%94y%C4%A0%C8%85g%C7%8A%22%C6%B0%C6%B2%20%C6%B4%C6%B6%C6%B8E%C5%91mo%C6%BD%C6%97s%C8%88%C4%87%C8%8A%C8%BD%20%C8%8E%C6%8E%C8%ADWil%C6%8C%C9%90Chapm%C7%B2%C7%98B%C9%8A%C6%99C%C6%9A%C7%9C%C9%92%C6%ADIA%C7%9CS%C9%AD%C4%9B%C4%8BB%C9%B3%C9%A9%C9%B5%C9%B7%C7%AAu%C8%B1%22%C8%BAFutu%C8%AC%C6%9D%C7%99i%C7%AC%C7%AE%C6%8Am%C6%97y%C6%9D%C5%A4%C5%A6%C9%B0k%C9%9D-S%C9%A8%C6%97%C9%BD%C5%AA%C6%BD%20C%C4%88%C4%A4Jagu%C8%8C%C7%9C%C6%85%C6%87T%C6%BB%C7%BD%C5%94%C5%96%C9%9F%C5%B6%C8%8B%C6%98%C8%A1%C8%A3%C5%8E%C5%90%C8%A6%C8%A8%C8%AA%C8%AC%C8%92J%C4%9Ag%C6%A2%C8%96n%C4%B0Ev%C4%B2%CA%8D%C7%A7%CA%82%C9%B4%C6%9D%C9%BE%C4%96k%C9%9E%C4%AA%CB%94%CA%80%C9%B2%C9%B4A%C9%B6%C6%9B%20%CA%BC%C6%8E%C8%88O%CA%8Dw%C4%9A%C4%A4%C4%B0%CB%A9%C4%AA%CB%A9]&display=TenPools";
        $puppeteer = new Puppeteer;
        dd($url);
        $browser = $puppeteer->launch(["defaultViewport" => ['width' => 1300, 'height' => 512]]);
        $page = $browser->newPage();
        $page->goto($url);
        $imageString = $page->screenshot([
            'encoding' => 'base64',
            'type' => 'png',
            'clip' => [
                'x' => 266,
                'y' => 0,
                'width' => 1024,
                'height' => 512,
            ],
        ]);
        $browser->close();
        return [
            "ImageString" => $imageString,
        ];
    }

    public function fetchEventFromExposure($event_id)
    {
        $api_key = env("EXPOSURE_API_KEY");

        // Create timestamp
        $datetime = new \DateTime();
        $datetime->setTimezone(new \DateTimeZone('UTC'));
        $timestamp = $datetime->format('Y-m-d\TH:i:s.u\Z');

        // Create hashString
        $path = $api_key . '&get&' . $timestamp . '&/api/v1/games';
        $message = strtoupper($path);
        $secret_key = env("EXPOSURE_SECRET_KEY");
        $hash = hash_hmac('sha256', $message, $secret_key, true);
        $hashString = base64_encode($hash);

        $headers = [
            'Timestamp:' . $timestamp,
            'Authentication:' . $api_key . '.' . $hashString,
            "Content-Type: application/json",
            'Accept: application/json',
            'Content-length: 0',
        ];

        $url = "https://basketball.exposureevents.com/api/v1/games?eventid=" . $event_id;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $event = trim(curl_exec($ch));
        $event = json_decode($event);

        return $event;

    }

    public function getDivisionNames($event)
    {
        $games = $event->Games->Results;
        $divisionNames = [];
        foreach ($games as $game) {
            $pool = $game->Division->Name;
            $divisionNames[$pool] = true;
        }
        return array_keys($divisionNames);
    }

    public function abbrTeamName($teamName)
    {
        $teamName = str_replace("Basketball", "BBall", $teamName);
        $teamName = str_replace("Academy", "ACAD", $teamName);
        $teamName = str_replace("17U", "", $teamName);
        $teamName = str_replace("16U", "", $teamName);
        $teamName = str_replace("15U", "", $teamName);
        $teamName = str_replace("14U", "", $teamName);
        $teamName = str_replace("13U", "", $teamName);
        $teamName = str_replace("12U", "", $teamName);
        $teamName = str_replace("11U", "", $teamName);
        $teamName = str_replace("10U", "", $teamName);
        $teamName = str_replace("17u", "", $teamName);
        $teamName = str_replace("15u", "", $teamName);
        $teamName = str_replace("16u", "", $teamName);
        $teamName = str_replace("14u", "", $teamName);
        $teamName = str_replace("13u", "", $teamName);
        $teamName = str_replace("12u", "", $teamName);
        $teamName = str_replace("11u", "", $teamName);
        $teamName = str_replace("10u", "", $teamName);
        $teamName = str_replace("Alabama", "AL", $teamName);
        $teamName = str_replace("Alaska", "AK", $teamName);
        $teamName = str_replace("American Samoa", "AS", $teamName);
        $teamName = str_replace("Arizona", "AZ", $teamName);
        $teamName = str_replace("Arkansas", "AR", $teamName);
        $teamName = str_replace("California", "CA", $teamName);
        $teamName = str_replace("Colorado", "CO", $teamName);
        $teamName = str_replace("Connecticut", "CT", $teamName);
        $teamName = str_replace("Delaware", "DE", $teamName);
        $teamName = str_replace("Dist. of Columbia", "DC", $teamName);
        $teamName = str_replace("Florida", "FL", $teamName);
        $teamName = str_replace("Georgia", "GA", $teamName);
        $teamName = str_replace("Guam", "GU", $teamName);
        $teamName = str_replace("Hawaii", "HI", $teamName);
        $teamName = str_replace("Idaho", "ID", $teamName);
        $teamName = str_replace("Illinois", "IL", $teamName);
        $teamName = str_replace("Indiana", "IN", $teamName);
        $teamName = str_replace("Iowa", "IA", $teamName);
        $teamName = str_replace("Kansas", "KS", $teamName);
        $teamName = str_replace("Kentucky", "KY", $teamName);
        $teamName = str_replace("Louisiana", "LA", $teamName);
        $teamName = str_replace("Maine", "ME", $teamName);
        $teamName = str_replace("Maryland", "MD", $teamName);
        $teamName = str_replace("Marshall Islands", "MH", $teamName);
        $teamName = str_replace("Massachusetts", "MA", $teamName);
        $teamName = str_replace("Michigan", "MI", $teamName);
        $teamName = str_replace("Micronesia", "FM", $teamName);
        $teamName = str_replace("Minnesota", "MN", $teamName);
        $teamName = str_replace("Mississippi", "MS", $teamName);
        $teamName = str_replace("Missouri", "MO", $teamName);
        $teamName = str_replace("Montana", "MT", $teamName);
        $teamName = str_replace("Nebraska", "NE", $teamName);
        $teamName = str_replace("New Hampshire", "NH", $teamName);
        $teamName = str_replace("New Jersey", "NJ", $teamName);
        $teamName = str_replace("New Mexico", "NM", $teamName);
        $teamName = str_replace("New York", "NY", $teamName);
        $teamName = str_replace("North Carolina", "NC", $teamName);
        $teamName = str_replace("North Dakota", "ND", $teamName);
        $teamName = str_replace("Northern Marianas", "MP", $teamName);
        $teamName = str_replace("Ohio", "OH", $teamName);
        $teamName = str_replace("Oklahoma", "OK", $teamName);
        $teamName = str_replace("Oregon", "OR", $teamName);
        $teamName = str_replace("Palau", "PW", $teamName);
        $teamName = str_replace("Pennsylvania", "PA", $teamName);
        $teamName = str_replace("Puerto Rico", "PR", $teamName);
        $teamName = str_replace("Rhode Island", "RI", $teamName);
        $teamName = str_replace("South Carolina", "SC", $teamName);
        $teamName = str_replace("South Dakota", "SD", $teamName);
        $teamName = str_replace("Tennessee", "TN", $teamName);
        $teamName = str_replace("Texas", "TX", $teamName);
        $teamName = str_replace("Utah", "UT", $teamName);
        $teamName = str_replace("Vermont", "VT", $teamName);
        $teamName = str_replace("Virginia", "VA", $teamName);
        $teamName = str_replace("Virgin Islands", "VI", $teamName);
        $teamName = str_replace("Washington", "WA", $teamName);
        $teamName = str_replace("West Virginia", "WV", $teamName);
        $teamName = str_replace("Wisconsin", "WI", $teamName);
        $teamName = str_replace("Wyoming", "WY", $teamName);
        return $teamName;
    }

    public function getTeamRecords($event)
    {
        $games = $event->Games->Results;
        $teams = [];
        foreach ($games as $game) {
            if (!array_key_exists($game->HomeTeam->TeamId, $teams)) {
                $teams[$game->HomeTeam->TeamId] = [
                    "Name" => $this->abbrTeamName($game->HomeTeam->Name),
                    "Wins" => 0,
                    "Loses" => 0,
                ];
            }
            if (!array_key_exists($game->AwayTeam->Name, $teams)) {
                $teams[$game->AwayTeam->TeamId] = [
                    "Name" => $this->abbrTeamName($game->AwayTeam->Name),
                    "Wins" => 0,
                    "Loses" => 0,
                ];
            }
            if ($game->HomeTeam->Score > $game->AwayTeam->Score) {
                $teams[$game->HomeTeam->TeamId]["Wins"] += 1;
                $teams[$game->AwayTeam->TeamId]["Loses"] += 1;
            } elseif ($game->HomeTeam->Score < $game->AwayTeam->Score) {
                $teams[$game->HomeTeam->TeamId]["Loses"] += 1;
                $teams[$game->AwayTeam->TeamId]["Wins"] += 1;
            }
        }
        return $teams;
    }

    public function getPools($event, $divisionName, $teamRecords)
    {
        $pools = [];
        $games = $event->Games->Results;
        foreach ($games as $game) {

            if ($divisionName != $game->Division->Name) {
                continue;
            }

            // Home Team
            $poolName = $game->HomeTeam->PoolName;

            if (!array_key_exists($poolName, $pools)) {
                $pools[$poolName] = [];
            }

            if (!in_array($teamRecords[$game->HomeTeam->TeamId], $pools[$poolName])) {
                array_push($pools[$poolName], $teamRecords[$game->HomeTeam->TeamId]);
            }

            // Away Team
            $poolName = $game->AwayTeam->PoolName;

            if (!array_key_exists($poolName, $pools)) {
                $pools[$poolName] = [];
            }

            if (!in_array($teamRecords[$game->AwayTeam->TeamId], $pools[$poolName])) {
                array_push($pools[$poolName], $teamRecords[$game->AwayTeam->TeamId]);
            }
        }
        ksort($pools);
        foreach ($pools as $pool => $teams) {
            usort($pools[$pool], function ($a, $b) {
                return $a["Wins"] < $b["Wins"];
            });
        }
        return $pools;
    }

    public function getDivisions($event)
    {
        $divisionNames = $this->getDivisionNames($event);
        $teamRecords = $this->getTeamRecords($event);
        $games = $event->Games->Results;
        $divisions = [];
        foreach ($divisionNames as $divisionName) {
            $pools = $this->getPools($event, $divisionName, $teamRecords);
            $divisions[$divisionName] = ["Pools" => $pools];
        }
        ksort($divisions);
        return $divisions;
    }

    public function getEvent($event_id)
    {
        $event = $this->fetchEventFromExposure($event_id);
        $eventName = $event->Games->Results[0]->Division->Event->Name;
        $divisions = $this->getDivisions($event);
        return [
            "Name" => $eventName,
            "Divisions" => $divisions,
        ];
    }

}
