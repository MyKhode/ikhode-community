```
docker exec -it ikhode-community-app bash
```

```
nohup cloudflared tunnel run mytunnel &> /home/vitoupro/cloudflared.log &
```

#### Step 5: Configure the Cloudflare Tunnel
```
tail -f /home/vitoupro/cloudflared.log
```