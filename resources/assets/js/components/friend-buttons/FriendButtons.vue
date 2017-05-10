<template>
    <div>
        <add-button v-if="notFriend" @update-state="setState" 
            :profile-user-id="profileUserId">
        </add-button>

        <remove-button v-if="isFriend" @update-state="setState" 
            :profile-user-id="profileUserId">
        </remove-button>
        
        <incoming-button v-if="hasReceivedFriendRequest" @update-state="setState" 
            :profile-user-id="profileUserId">
        </incoming-button>
        
        <outgoing-button v-if="hasSentFriendRequest" @update-state="setState" 
            :profile-user-id="profileUserId">
        </outgoing-button>
    </div>
</template>

<script>
    import store from './../store';
    import FriendshipParser from './../FriendshipParser';
    
    import AddButton from './AddButton.vue';
    import RemoveButton from './RemoveButton.vue';
    import OutgoingButton from './OutgoingButton.vue';
    import IncomingButton from './IncomingButton.vue';

    import { 
        FRIEND_STATE,
        NOT_FRIEND_STATE, 
        REQUEST_SENT_STATE, 
        REQUEST_RECEIVED_STATE,
    } from './../config';
    
    export default {
        props: ['currentUserId', 'profileUserId', 'friendship'],

        computed: {
            /**
             * @return boolean
             */
            isFriend () {
                return this.state == FRIEND_STATE;
            },

            /**
             * @return boolean
             */
            hasSentFriendRequest () {
                return this.state == REQUEST_SENT_STATE;
            },

            /**
             * @return boolean
             */
            hasReceivedFriendRequest () {
                return this.state == REQUEST_RECEIVED_STATE;
            },

            /**
             * @return boolean
             */
            notFriend () {
                return this.state == NOT_FRIEND_STATE;
            },
        },

        mounted () {
            this.setState(this.defineCurrentState());
        },

        data () {
            return {
                /**
                 * @type int
                 */
                state: store.state.friendButton
            }
        },

        methods: {
            /**
             * @return void
             */
            defineCurrentState () {
                return FriendshipParser.apply(this.friendship, this.currentUserId);
            },

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