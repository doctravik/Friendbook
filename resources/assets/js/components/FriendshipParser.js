import { 
    NOT_FRIEND_STATE, 
    REQUEST_SENT_STATE, 
    REQUEST_RECEIVED_STATE,
    FRIEND_STATE,
    FRIENDSHIP_PENDING_STATE,
    FRIENDSHIP_ACCEPTED_STATE
} from './config';

class FriendshipParser {
    /**
     * @param object friendship
     * @param int userId
     * @return void
     */
    constructor (friendship, userId) {
        this.friendship = friendship;
        this.userId = userId;
    }

    /**
     * Initialize FriendshipParser constructor.
     * 
     * @param object friendship
     * @param int userId
     * @return FriendshipParser
     */
    static apply (friendship, userId) {
        return new FriendshipParser(friendship, userId).parse();
    }

    /**
     * @return int
     */
    parse () {
        if (this.hasPendingState()) {
            if (this.isSender()) {
                return REQUEST_SENT_STATE;
            } else {
                return REQUEST_RECEIVED_STATE;
            }
        } else if (this.hasAcceptedState()) {
            return FRIEND_STATE
        } else {
            return NOT_FRIEND_STATE;
        }
    }

    /**
     * @return boolean
     */
    isSender () {
        return this.userId == this.friendship.requester_id;
    }

    /**
     * @return boolean
     */
    hasPendingState () {
        return this.friendship && this.friendship.status == FRIENDSHIP_PENDING_STATE;
    }

    /**
     * @return boolean
     */
    hasAcceptedState () {
        return this.friendship && this.friendship.status == FRIENDSHIP_ACCEPTED_STATE;
    }
}

export default FriendshipParser;