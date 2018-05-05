<?php

$app->post('/profile', 'UserController:chkloginUser');
$app->get('/userslist', 'UserController:usersList');
$app->get('/eventslist', 'UserController:eventsList');
$app->get('/user/', 'UserController:usersInfo');
$app->get('/slap', 'UserController:coolingSlap');
$app->group('/api', function () {

    $this->post(
            '/addUserDevice', 'UserController:addUserDevice'
    );
    $this->post(
            '/removeUserDevice', 'UserController:removeUserDevice'
    );
    $this->post(
            '/registerUser', 'UserController:creatUser'
    );
    $this->post(
            '/loginUser', 'UserController:loginUser'
    );
    $this->post(
            '/verifyUsername', 'UserController:verfyUserName'
    );
    $this->post(
            '/verifyUserEmail', 'UserController:verfyUserEmail'
    );
    $this->post(
            '/forgotPassword', 'UserController:forgotPassword'
    );
    $this->post(
            '/resendCode', 'UserController:resendOTP'
    );
    $this->post(
            '/verifyCode', 'UserController:verifyOTP'
    );
    $this->post(
            '/searchUser', 'UserController:searchUser'
    );
    $this->post(
            '/uploadAvatar', 'UserController:uploadAvatar'
    );
    $this->post(
            '/getFriends', 'UserController:getFriendList'
    );
    $this->post(
            '/getUserFriends', 'UserController:getUserFriendList'
    );
    $this->post(
            '/addFollow', 'UserNetworkController:addToUserNetwork'
    );
    $this->post(
            '/networkrequestlist', 'UserNetworkController:networkRequestList'
    );
    $this->post(
            '/changeFollowStatus', 'UserNetworkController:changeNetworkStatus'
    );
    $this->post(
            '/getMyFollows', 'UserNetworkController:getMyFollows'
    );
    $this->post(
            '/createUpdateEvent', 'EventController:creatEvent'
    );
    $this->post(
            '/uploadPromotionImages', 'EventController:uploadPromotionImages'
    );
    $this->post(
            '/getFeedList', 'EventController:eventList'
    );
    $this->post(
            '/getFeedListFilter', 'EventController:getFeedListFilter'
    );
    $this->post(
            '/setEventVenue', 'EventController:setEventVenue'
    );
    $this->post(
            '/eventInfo', 'EventController:geteventInfo'
    );
    $this->post(
            '/setAssociatedevents', 'EventController:setAssociatedevents'
    );
    $this->post(
            '/getAssociatedevents', 'EventController:getAssociatedevents'
    );
    $this->post(
            '/inviteToEvent', 'EventController:inviteToEvent'
    );
    $this->post(
            '/getinvitations', 'EventController:getInvitations'
    );
    $this->post(
            '/acceptRejectInvitation', 'EventController:updateInvitations'
    );
    $this->post(
            '/addCommentToEvent', 'EventController:addCommentToEvent'
    );
    $this->post(
            '/getEventComments', 'EventController:getEventComments'
    );
    $this->delete(
            '/removecommentevent', 'EventController:removeCommentEvent'
    );
    $this->post(
            '/addHost', 'EventController:addCoHost'
    );
    $this->post(
            '/acceptRejectCoHostInvitation', 'EventController:acceptRejectCoHostInvitation'
    );
    $this->delete(
            '/removeHost', 'EventController:removeCoHost'
    );
    $this->post(
            '/chkloginuser', 'UserController:loginUser'
    );
    $this->post(
            '/usersrank', 'UserController:usersRank'
    );
    $this->post(
            '/setEventPosts', 'EventController:setEventPosts'
    );
    $this->post(
            '/getEventPosts', 'EventController:getEventPosts'
    );
    $this->post(
            '/setUserPosts', 'UserController:setUserPosts'
    );
    $this->post(
            '/getUserPosts', 'UserController:getUserPosts'
    );
    $this->post(
            '/commentOnPost', 'UserController:setPostComments'
    );
    $this->post(
            '/getPostComments', 'UserController:getPostComments'
    );
    $this->post(
            '/postTo', 'UserController:setUserPosts'
    );
    $this->post(
            '/UploadVideoThumbnail', 'UserController:UploadVideoThumbnail'
    );
    $this->post(
            '/GetFriendsLatestPosts', 'UserController:GetFriendsLatestPosts'
    );
    $this->post(
            '/GetFriendPost', 'UserController:GetFriendPost'
    );
    $this->post(
            '/updatepostmedialike', 'UserController:update_post_media_like'
    );
    $this->post(
            '/addCommentToPost', 'UserController:addCommentToPost'
    );
    $this->post(
            '/markReadOnPost', 'UserController:markReadOnPost'
    );
    $this->post(
            '/getUserMesssages', 'UserController:getUserMesssages'
    );
    $this->post(
            '/getMyCreatedEvents', 'EventController:getMyCreatedEvents'
    );
    $this->post(
            '/editProfile', 'UserController:editProfile'
    );
    $this->post(
            '/getUserEvents', 'EventController:getUserEvents'
    );
    $this->post(
            '/eventPostsTo', 'EventController:eventPostsTo'
    );
    $this->delete(
            '/removeEventGuest', 'EventController:removeEventGuest'
    );
    $this->post(
            '/getUserProfile', 'UserController:getUserProfile'
    );
    $this->post(
            '/getUserPostEventFriend', 'UserController:getUserPostEventFriend'
    );
    $this->post(
            '/GetEventRecords', 'EventController:GetEventRecords'
    );
    $this->post(
            '/getSharedPost', 'UserController:getSharedPost'
    );
    $this->post(
            '/getCities', 'UserController:getcities'
    );
    $this->post(
            '/getnotifications', 'UserController:getnotifications'
    );
    $this->post(
            '/updateOneSignalNotifyFlag', 'UserController:updateOneSignalNotifyFlag'
    );
    $this->post(
            '/getmyevents', 'EventController:getmyevents'
    );
    $this->post(
            '/updateeventmedialike', 'EventController:update_event_media_like'
    );
    $this->post(
            '/markNotificationAsRead', 'UserController:markNotificationAsRead'
    );
    $this->post(
            '/deleteNotification', 'UserController:deleteNotification'
    );
    $this->post(
            '/deleteAllUserNotification', 'UserController:deleteAllUserNotification'
    );

    $this->post(
            '/getUserPostnotifications', 'UserController:getUserPostnotifications'
    );
    $this->post(
            '/markpostNotificationAsRead', 'UserController:markpostNotificationAsRead'
    );
    $this->post(
            '/deletepostNotification', 'UserController:deletepostNotification'
    );
    $this->post(
            '/deleteAllUserpostNotification', 'UserController:deleteAllUserpostNotification'
    );
    $this->post(
            '/getComments', 'EventController:getComments'
    );
    $this->post(
            '/deleteevent', 'EventController:deleteevent'
    );
    $this->post(
            '/deleteeventpost', 'EventController:deleteeventpost'
    );
    $this->post(
            '/deleteuserpost', 'UserController:deleteuserpost'
    );
    $this->post(
            '/userTwitterInfo', 'UserController:userTwitterInfo'
    );
});
