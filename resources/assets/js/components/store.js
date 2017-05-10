/**
 * Centralized store for states.
 * 
 * @type object
 */
let store = {
    state: {
        friendButton: null
    },

    /**
     * @param int state
     * void
     */
    setFriendButtonState (state) {
        this.state.friendButton = state; 
    }
}

export default store;