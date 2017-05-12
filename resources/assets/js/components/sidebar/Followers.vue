<script>
    import People from './People.vue';

    export default {
        props: ['user'],
        
        data () {
            return {
                url: '#',
                title: 'Followers',
                users: this.user.followers,
                count: this.user.followers_count
            }
        },

        methods: {
            /**
             * @return void
             */
            listenEvents () {
                eventDispatcher.$on('add-follower', user => {
                    this.addUser(user);
                });
            }
        },

        mixins: [ People ]
    }
</script>