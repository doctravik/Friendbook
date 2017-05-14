<template>
    <div>
        <add-button v-if="notFriend" @update-state="setState" 
            :current-user="currentUser"
            :profile-user-id="profileUserId">
        </add-button>

        <remove-button v-if="isFriend" @update-state="setState"
            :current-user="currentUser"
            :profile-user-id="profileUserId">
        </remove-button>
        
        <incoming-button v-if="hasReceivedFriendRequest" @update-state="setState"
            :current-user="currentUser"
            :profile-user-id="profileUserId">
        </incoming-button>
        
        <outgoing-button v-if="hasSentFriendRequest" @update-state="setState" 
            :profile-user-id="profileUserId">
        </outgoing-button>
    </div>
</template>

<script>
    import store from './../store';    
    import * as buttonState from './../config';
    import AddButton from './AddButton.vue';
    import RemoveButton from './RemoveButton.vue';
    import OutgoingButton from './OutgoingButton.vue';
    import IncomingButton from './IncomingButton.vue';
    
    export default {
        props: ['currentUser', 'profileUserId', 'friendButtonState'],

        computed: {
            /**
             * @return boolean
             */
            currentUserId () {
                return this.currentUser.id;
            },

            /**
             * @return boolean
             */
            isFriend () {
                return this.state == buttonState.FRIEND_STATE;
            },

            /**
             * @return boolean
             */
            hasSentFriendRequest () {
                return this.state == buttonState.REQUEST_SENT_STATE;
            },

            /**
             * @return boolean
             */
            hasReceivedFriendRequest () {
                return this.state == buttonState.REQUEST_RECEIVED_STATE;
            },

            /**
             * @return boolean
             */
            notFriend () {
                return this.state == buttonState.NOT_FRIEND_STATE;
            },
        },

        data () {
            return {
                /**
                 * @type int
                 */
                state: this.friendButtonState
            }
        },

        methods: {
            /**
             * @param void
             */
            setState (state) {
                store.setFriendButtonState(state);

                this.state = store.state.friendButton;
            }

        },

        components: {
            AddButton, RemoveButton, IncomingButton, OutgoingButton
        }
    }
</script>