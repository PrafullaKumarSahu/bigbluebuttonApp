<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\DeleteRecordingsParameters;

class BigBlueButtonController extends Controller
{
    public function createMeeting(){
        $meetingID = 'random-1665691pk';
        $meetingName = 'random-1665691pk';
        $recordID = 'random-1665691pk';
        $attendee_password = 'ap';
        $moderator_password = 'mp';
        $duration = '';
        $urlLogout = '#';
        $isRecordingTrue = true;

        $bbb = new BigBlueButton();

        $createMeetingParams = new CreateMeetingParameters($meetingID, $meetingName);
        $createMeetingParams->setAttendeePassword($attendee_password);
        $createMeetingParams->setModeratorPassword($moderator_password);
        $createMeetingParams->setDuration($duration);
        $createMeetingParams->setLogoutUrl($urlLogout);
        if ($isRecordingTrue) {
            $createMeetingParams->setRecord(true);
            $createMeetingParams->setAllowStartStopRecording(true);
            $createMeetingParams->setAutoStartRecording(true);
        }

        //dd($bbb);
        $response = $bbb->createMeeting($createMeetingParams);
        //dd($response);
        if ($response->getReturnCode() == 'FAILED') {
            return 'Can\'t create room! please contact our administrator.';
        } else {
            // process after room created
            return $response->getMeetingId();
        }
    }

    public function getMeetingInfo($meetingID, $moderator_password){
        $bbb = new BigBlueButton();

        $getMeetingInfoParams = new GetMeetingInfoParameters($meetingID, '', $moderator_password);
        $response = $bbb->getMeetingInfo($getMeetingInfoParams);
        if ($response->getReturnCode() == 'FAILED') {
            // meeting not found or already closed
            echo 'failed';
        } else {
            // process $response->getRawXml();
            return $response->getRawXml();
        }
    }

    public function joinMeeting(){
        $meetingID = 'random-1665691pk';
        //$meetingID = $this->createMeeting();
        $name = 'random-1665691pk';
        $password = 'mp';

        $bbb = new BigBlueButton();

        // $moderator_password for moderator
        $joinMeetingParams = new JoinMeetingParameters($meetingID, $name, $password);
        $joinMeetingParams->setRedirect(true);
        $url = $bbb->getJoinMeetingURL($joinMeetingParams);
        //return redirect($url);
        return $url;
    }

    public function getMeetings()
    {
        $bbb = new BigBlueButton();
        $response = $bbb->getMeetings();

        if ($response->getReturnCode() == 'SUCCESS') {
            foreach ($response->getRawXml()->meetings->meeting as $meeting) {
                $meetingCollections[] = $meeting;
            }
        }

        return collect($meetingCollections);
    }

    public function closeMeeting()
    {
        $meetingID = 'random-1665691pk';
        $moderator_password = 'mp';

        $bbb = new BigBlueButton();

        $endMeetingParams = new EndMeetingParameters($meetingID, $moderator_password);
        $response = $bbb->endMeeting($endMeetingParams);
    }

    public function getRecordings()
    {
        $recordingParams = new GetRecordingsParameters();
        $bbb = new BigBlueButton();
        $response = $bbb->getRecordings($recordingParams);

        if ($response->getReturnCode() == 'SUCCESS') {
            foreach ($response->getRawXml()->recordings->recording as $recording) {
                // process all recording
            }
        }
    }

    public function deleteRecordings()
    {
        $bbb = new BigBlueButton();
        $deleteRecordingsParams= new DeleteRecordingsParameters($recordingID); // get from "Get Recordings"
        $response = $bbb->deleteRecordings($deleteRecordingsParams);

        if ($response->getReturnCode() == 'SUCCESS') {
            // recording deleted
        } else {
            // something wrong
        }
    }
}