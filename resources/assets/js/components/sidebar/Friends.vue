<script>
    import People from './People.vue';

    export default {
        props: ['friends', 'friendsCount'],
        
        data () {
            return {
                url: '#',
                title: 'Friends',
                users: this.friends,
                count: this.friendsCount
            }
        },

        methods: {
            /**
             * @return void
             */
            listenEvents () {
                eventDispatcher.$on('add-friend', user => {
                    this.addUser(user);
                });
            }
        },

        mixins: [ People ]
    }
</script>