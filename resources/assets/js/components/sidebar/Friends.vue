<script>
    import People from './People.vue';

    export default {
        props: ['user', 'friends', 'friendsCount'],
        
        data () {
            return {
                url: this.user.slug + '/friends',
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

                eventDispatcher.$on('remove-friend', userId => {
                    this.deleteUser(userId);
                });
            }
        },

        mixins: [ People ]
    }
</script>